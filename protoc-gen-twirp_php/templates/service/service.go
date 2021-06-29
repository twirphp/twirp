package service

import (
	"embed"
	"io/fs"
)

//go:embed *.php.tmpl
var files embed.FS

// FS returns a filesystem with the templates.
func FS() fs.FS {
	return files
}
