package proto

import (
	"google.golang.org/protobuf/compiler/protogen"
)

// MethodFullName creates a fully qualified name for the service.
func MethodFullName(method *protogen.Method) string {
	return string(method.Parent.Desc.FullName()) + "/" + string(method.Desc.Name())
}
