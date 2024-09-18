package main

import (
	"github.com/twirphp/twirp/.dagger/internal/dagger"
)

// Generate code using the current version of the code generator plugin.
func (m *Twirp) Generate() *Generate {
	return &Generate{
		Main: m,
	}
}

type Generate struct {
	// +private
	Main *Twirp
}

func (m *Generate) Example() *dagger.Directory {
	return m.Main.generate(m.Main.Source.Directory("example"))
}
