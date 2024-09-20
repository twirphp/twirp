package main

import (
	"dagger/twirp/internal/dagger"
	"fmt"
)

type Twirp struct {
	// Project source directory
	// This will become useful once pulling from remote becomes available
	//
	// +private
	Source *dagger.Directory
}

func New(
	// Project source directory.
	//
	// +defaultPath=/
	// +ignore=[".devenv", ".direnv", ".github", ".pre-commit-config.yaml"]
	source *dagger.Directory,
) (*Twirp, error) {
	return &Twirp{
		Source: source,
	}, nil
}

func (m *Twirp) Test() *dagger.Container {
	return dag.Container().From("alpine").WithWorkdir("/work").WithMountedDirectory("/work", m.Source).Terminal()
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
		Exec([]string{"go", "install", fmt.Sprintf("github.com/twitchtv/twirp/clientcompat@%s", twirpVersion)}).
		File("/go/bin/clientcompat")
}

// Build clientcompat binary.
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

	return dag.Go(dagger.GoOpts{
		Version: goVersion,
	}).
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
