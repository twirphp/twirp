package proto

import (
	"errors"
	"strconv"

	"google.golang.org/protobuf/types/descriptorpb"
)

// FullName is a template wrapper for protocol buffer descriptor names.
func FullName(desc ...interface{}) (string, error) {
	switch len(desc) {
	case 2:
		file := desc[0].(*descriptorpb.FileDescriptorProto)
		svc := desc[1].(*descriptorpb.ServiceDescriptorProto)

		return ServiceFullName(file, svc), nil

	case 3:
		file := desc[0].(*descriptorpb.FileDescriptorProto)
		svc := desc[1].(*descriptorpb.ServiceDescriptorProto)
		method := desc[2].(*descriptorpb.MethodDescriptorProto)

		return MethodFullName(file, svc, method), nil

	default:
		return "", errors.New("unexpected amount of arguments (expected 2 or 3, got " + strconv.Itoa(len(desc)))
	}
}

// ServiceFullName creates a fully qualified name for the service.
func ServiceFullName(file *descriptorpb.FileDescriptorProto, svc *descriptorpb.ServiceDescriptorProto) string {
	prefix := ""

	if pkg := file.GetPackage(); pkg != "" {
		prefix = pkg + "."
	}

	return prefix + svc.GetName()
}

// MethodFullName creates a fully qualified name for the service.
func MethodFullName(file *descriptorpb.FileDescriptorProto, svc *descriptorpb.ServiceDescriptorProto, method *descriptorpb.MethodDescriptorProto) string {
	return ServiceFullName(file, svc) + "/" + method.GetName()
}
