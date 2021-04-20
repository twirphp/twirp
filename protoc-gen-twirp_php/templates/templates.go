package templates

import (
	"embed"
	"io/fs"
)

//go:embed global/*.php service/*.php
var files embed.FS

// FS returns a filesystem with the templates.
func FS() fs.FS {
	return files
}
