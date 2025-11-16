package main

import (
	"context"

	"github.com/sagikazarmark/dagx/pipeline"
	"github.com/twirphp/twirp/.dagger/internal/dagger"
)

// Run e2e tests.
func (m *Twirp) Tests(
	// +optional
	phpVersion string,
) *Tests {
	if phpVersion == "" {
		phpVersion = defaultPhpVersion
	}

	return &Tests{
		Main:       m,
		PhpVersion: phpVersion,
	}
}

type Tests struct {
	// +private
	Main *Twirp

	// +private
	PhpVersion string
}

func (m *Tests) All(ctx context.Context) error {
	p := pipeline.New(ctx)

	pipeline.AddStep(p, m.Etoe().All)
	pipeline.AddSyncStep(p, m.Phpunit())

	return pipeline.Run(p)
}

func (m *Tests) Phpunit() *dagger.Container {
	return phpBase(m.PhpVersion).Container().
		WithDirectory("/work", m.Main.Source).
		WithWorkdir("/work").
		WithExec([]string{"composer", "install"}).
		WithEnvVariable("XDEBUG_MODE", "coverage").
		WithExec([]string{"lib/vendor/bin/phpunit"}) // -v --coverage-clover ${BUILD_DIR}/coverage-php.xml
}
