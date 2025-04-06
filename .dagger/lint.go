package main

import (
	"context"

	"github.com/sagikazarmark/dagx/pipeline"
	"github.com/twirphp/twirp/.dagger/internal/dagger"
)

// Run linters.
func (m *Twirp) Lint() *Lint {
	return &Lint{
		Main: m,
	}
}

type Lint struct {
	// +private
	Main *Twirp
}

func (m *Lint) All(ctx context.Context) error {
	p := pipeline.New(ctx)

	pipeline.AddSyncStep(p, m.Go())
	pipeline.AddSyncStep(p, m.Phpstan())
	pipeline.AddSyncStep(p, m.PhpCsFixer())

	return pipeline.Run(p)
}

func (m *Lint) Go() *dagger.Container {
	return dag.GolangciLint(dagger.GolangciLintOpts{
		Version: golangciLintVersion,
		Cache:   cacheVolume("golangci-lint"),

		// do not use goBase here for now to avoid parallel jobs overwriting cache volumes
		Container: dag.Go(dagger.GoOpts{Version: goVersion}).
			WithModuleCache(cacheVolume("golangci-lint-go-mod")).
			WithBuildCache(cacheVolume("golangci-lint-go-build")).
			Container(),
	}).Run(m.Main.Source, dagger.GolangciLintRunOpts{Verbose: true})
}

func (m *Lint) Phpstan() *dagger.Container {
	return dag.Phpstan(dagger.PhpstanOpts{
		Version:    phpstanVersion,
		PhpVersion: defaultPhpVersion,
	}).Analyse(m.Main.source())
}

func (m *Lint) PhpCsFixer() *dagger.Container {
	return dag.PhpCsFixer(dagger.PhpCsFixerOpts{
		Version:    phpCsFixerVersion,
		PhpVersion: defaultPhpVersion,
	}).Check(m.Main.source())
}
