package main

import (
	"strings"

	"github.com/golang/protobuf/protoc-gen-go/descriptor"
)

// PhpNamespace guesses the namespace of the file according to the following order of precedence:
//
// 1. Explicitly set namespace using the "php_namespace" option
// 2. Package name with dots replaced with backslashes and segments converted to title
// 3. No (root) namespace
func PhpNamespace(file *descriptor.FileDescriptorProto) string {
	ns := file.GetOptions().GetPhpNamespace()

	if ns == "" && file.Package != nil {
		ns = PhpFQN(file.GetPackage())
	}

	return ns
}

// PhpFQN generates a fully qualified name from a proto reference.
func PhpFQN(s string) string {
	parts := strings.Split(s, ".")

	for key, value := range parts {
		parts[key] = strings.Title(value)
	}

	return strings.Join(parts, "\\")
}

// PhpPath guesses the path of the file based on the (internally calculated) namespace.
func PhpPath(file *descriptor.FileDescriptorProto) string {
	return strings.Replace(PhpNamespace(file), "\\", "/", -1)
}

// PhpPathFromNamespace guesses the path of the file based on the namespace.
func PhpPathFromNamespace(ns string) string {
	return strings.Replace(ns, "\\", "/", -1)
}

// PhpServiceName transforms the service name into a PHP compatible one.
func PhpServiceName(svc *descriptor.ServiceDescriptorProto) string {
	return svc.GetName()
}
