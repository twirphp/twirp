package main

import (
	"context"

	"github.com/sagikazarmark/dagx/pipeline"

	"github.com/twirphp/twirp/.dagger/internal/dagger"
)

// Run e2e tests.
func (m *Tests) Etoe() *Etoe {
	return &Etoe{
		Main:       m.Main,
		PhpVersion: m.PhpVersion,
	}
}

type Etoe struct {
	// +private
	Main *Twirp

	// +private
	PhpVersion string
}

func (m *Etoe) All(ctx context.Context) error {
	p := pipeline.New(ctx)

	pipeline.AddSyncSteps(p,
		m.Clientcompat(m.PhpVersion),
		m.Complete(m.PhpVersion),
		m.NoServices(m.PhpVersion),
	)

	if m.PhpVersion != "7.4" {
		pipeline.AddSyncStep(p, m.Namespace(m.PhpVersion))
	}

	return pipeline.Run(p)
}

func (m *Etoe) source(name string) *dagger.Directory {
	return m.Main.Source.Directory("tests/" + name)
}

func (m *Etoe) container(name string, phpVersion string) *dagger.Container {
	if phpVersion == "" {
		phpVersion = defaultPhpVersion
	}

	return phpBase(phpVersion).
		WithSource(m.Main.Source).
		WithComposerInstall().
		Container().
		WithWorkdir("/work/src/tests/"+name).
		WithMountedDirectory("generated", m.Main.generate(m.source(name)))
}

func (m *Etoe) Clientcompat(
	// +optional
	phpVersion string,
) *dagger.Container {
	return m.container("clientcompat", phpVersion).
		WithFile("/usr/local/bin/clientcompat", m.Main.Clientcompat("")).
		WithExec([]string{"clientcompat", "-client", "./compat.sh"})
}

func (m *Etoe) Complete(
	// +optional
	phpVersion string,
) *dagger.Container {
	return m.container("complete", phpVersion).
		WithExec([]string{"../../lib/vendor/bin/phpunit", "-v"})
}

func (m *Etoe) Namespace(
	// +optional
	phpVersion string,
) *dagger.Container {
	return m.container("namespace", phpVersion).
		WithExec([]string{"php", "test.php"})
}

func (m *Etoe) NoServices(
	// +optional
	phpVersion string,
) *dagger.Container {
	return m.container("no_services", phpVersion).
		WithExec([]string{"bash", "-c", `test ! -f generated/Twirp/Tests/No_services/Proto/TwirpError.php || (echo "TwirpError.php should not be generated when there are no services defined in any of the proto files"; exit 1)`})
}
