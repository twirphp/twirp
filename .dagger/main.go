package main

import (
	"context"
	"fmt"
	"strings"

	"github.com/containerd/platforms"

	"github.com/twirphp/twirp/.dagger/internal/dagger"
)

type Twirp struct {
	// Project source directory
	// This will become useful once pulling from remote becomes available
	//
	// +private
	Source *dagger.Directory

	// +private
	Platform dagger.Platform
}

func New(
	ctx context.Context,
	// Project source directory.
	//
	// +defaultPath=/
	// +ignore=[".devenv", ".direnv", ".github", "build", "example/generated", "lib/vendor", "tests/**/generated", ".pre-commit-config.yaml"]
	source *dagger.Directory,
) (*Twirp, error) {
	platform, err := dag.DefaultPlatform(ctx)
	if err != nil {
		return nil, err
	}

	return &Twirp{
		Source:   source,
		Platform: platform,
	}, nil
}

// Build clientcompat binary.
func (m *Twirp) Clientcompat(
	// Target platform in "[os]/[platform]/[version]" format (e.g., "darwin/arm64/v7", "windows/amd64", "linux/arm64").
	//
	// +optional
	platform dagger.Platform,
) *dagger.File {
	return dag.Go(dagger.GoOpts{
		Version: goVersion,
	}).
		WithCgoDisabled().
		Exec([]string{"go", "install", fmt.Sprintf("github.com/twitchtv/twirp/clientcompat@%s", twirpVersion)}).
		File("/go/bin/clientcompat")
}

// Build the code generator plugin.
func (m *Twirp) Build(
	// Target platform in "[os]/[platform]/[version]" format (e.g., "darwin/arm64/v7", "windows/amd64", "linux/arm64").
	//
	// +optional
	platform dagger.Platform,
) *dagger.File {
	return m.build(platform, "dev")
}

func (m *Twirp) build(platform dagger.Platform, version string) *dagger.File {
	if version == "" {
		version = "unknown"
	}

	if platform == "" {
		platform = m.Platform
	}

	return goBase().
		WithCgoDisabled().
		WithSource(m.Source).
		Build(dagger.GoWithSourceBuildOpts{
			Pkg:      "./protoc-gen-twirp_php",
			Trimpath: true,
			Ldflags: []string{
				"-s", "-w",
				"-X", "main.version=" + version,
			},
			Platform: platform,
		})
}

func (m *Twirp) protoc() *dagger.Container {
	platform := platforms.MustParse(string(m.Platform))

	// only support linux for now
	arch := "x86_64"
	if platform.Architecture == "arm64" {
		arch = "aarch_64"
	}

	const downloadUrlTemplate = "https://github.com/protocolbuffers/protobuf/releases/download/v%s/protoc-%s-linux-%s.zip"

	downloadUrl := fmt.Sprintf(downloadUrlTemplate, protobufVersion, protobufVersion, arch)

	// dag.HTTP doesn't preserve the filename?
	src := dag.Arc().Unarchive(dag.HTTP(downloadUrl).WithName("protoc.zip"))

	return dag.Container().
		From(alpineBaseImage).
		WithFile("/usr/local/bin/protoc", src.File("protoc/bin/protoc")).
		WithDirectory("/usr/local/include", src.Directory("protoc/include"))
}

func (m *Twirp) codegen() *dagger.Container {
	return m.protoc().
		WithFile("/usr/local/bin/protoc-gen-twirp_php", m.Build("")).
		WithWorkdir("/work")
}

func (m *Twirp) generate(source *dagger.Directory) *dagger.Directory {
	return m.codegen().
		WithDirectory("/work", source.WithDirectory("generated", dag.Directory())).
		WithExec([]string{"sh", "-c", strings.Join([]string{"protoc", "--twirp_php_out=generated/", "--php_out=generated/", "*.proto"}, " ")}).
		Directory("generated")
}

func (m *Twirp) source() *dagger.Directory {
	return phpBaseDefault().
		WithSource(m.Source).
		WithComposerInstall().
		Source()
}

func goBase() *dagger.Go {
	return dag.Go(dagger.GoOpts{Version: goVersion}).
		WithModuleCache(cacheVolume("go-mod")).
		WithBuildCache(cacheVolume("go-build"))
}

func phpBaseDefault() *dagger.Php {
	return phpBase("")
}

func phpBase(phpVersion string) *dagger.Php {
	if phpVersion == "" {
		phpVersion = defaultPhpVersion
	}

	return dag.Php(dagger.PhpOpts{
		Version: phpVersion,
	}).
		WithExtension("bcmath").
		WithComposer(dagger.PhpWithComposerOpts{
			Version: composerVersion,
		}).
		WithComposerCache(cacheVolume("composer"))
}

func cacheVolume(name string) *dagger.CacheVolume {
	return dag.CacheVolume(fmt.Sprintf("twirphp-%s", name))
}
