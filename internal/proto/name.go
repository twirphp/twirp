package proto

import (
	"errors"
	"strconv"

	"github.com/golang/protobuf/protoc-gen-go/descriptor"
)

// FullName is a template wrapper for protocol buffer descriptor names.
func FullName(desc ...interface{}) (string, error) {
	switch  len(desc) {
	case 2:
		file := desc[0].(*descriptor.FileDescriptorProto)
		svc := desc[1].(*descriptor.ServiceDescriptorProto)

		return ServiceFullName(file, svc), nil

	case 3:
		file := desc[0].(*descriptor.FileDescriptorProto)
		svc := desc[1].(*descriptor.ServiceDescriptorProto)
		method := desc[2].(*descriptor.MethodDescriptorProto)

		return MethodFullName(file, svc, method), nil

	default:
		return "", errors.New("unexpected amount of arguments (expected 2 or 3, got " + strconv.Itoa(len(desc)))
	}
}

// ServiceFullName creates a fully qualified name for the service.
func ServiceFullName(file *descriptor.FileDescriptorProto, svc *descriptor.ServiceDescriptorProto) string {
	prefix := ""

	if pkg := file.GetPackage(); pkg != "" {
		prefix = pkg + "."
	}

	return prefix + svc.GetName()
}

// MethodFullName creates a fully qualified name for the service.
func MethodFullName(file *descriptor.FileDescriptorProto, svc *descriptor.ServiceDescriptorProto, method *descriptor.MethodDescriptorProto) string {
	return ServiceFullName(file, svc) + "/" + method.GetName()
}
