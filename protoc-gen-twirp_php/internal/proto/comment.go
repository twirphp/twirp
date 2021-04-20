package proto

import (
	"strings"

	"google.golang.org/protobuf/compiler/protogen"
)

// SplitComments transforms a message name into a PHP compatible one.
func SplitComments(comments protogen.Comments) []string {
	return strings.Split(strings.TrimSuffix(string(comments), "\n"), "\n")
}
