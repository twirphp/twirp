package main

import (
	"fmt"
	"strconv"
	"strings"

	"github.com/golang/protobuf/protoc-gen-go/descriptor"
	"github.com/pkg/errors"
	"github.com/twitchtv/protogen/typemap"
)

// ProtoComment is a template wrapper for protocol buffer descriptor comments.
func ProtoComment(desc ...interface{}) (string, error) {
	switch  len(desc) {
	case 2:
		file := desc[0].(*descriptor.FileDescriptorProto)
		svc := desc[1].(*descriptor.ServiceDescriptorProto)

		return ProtoServiceComment(file, svc), nil

	case 3:
		file := desc[0].(*descriptor.FileDescriptorProto)
		svc := desc[1].(*descriptor.ServiceDescriptorProto)
		method := desc[2].(*descriptor.MethodDescriptorProto)

		return ProtoMethodComment(file, svc, method), nil

	default:
		return "", errors.New("unexpected amount of arguments (expected 2 or 3, got "+strconv.Itoa(len(desc)))
	}
}

// ProtoServiceComment extracts comments for a service.
func ProtoServiceComment(file *descriptor.FileDescriptorProto, svc *descriptor.ServiceDescriptorProto) string {
	reg := &typemap.Registry{}

	comments, err := reg.ServiceComments(file, svc)
	if err != nil {
		return ""
	}

	text := strings.TrimSuffix(comments.Leading, "\n")
	if len(strings.TrimSpace(text)) == 0 {
		return ""
	}

	return text
}

// ProtoMethodComment extracts comments for a service method.
func ProtoMethodComment(file *descriptor.FileDescriptorProto, svc *descriptor.ServiceDescriptorProto, method *descriptor.MethodDescriptorProto) string {
	reg := &typemap.Registry{}

	comments, err := reg.MethodComments(file, svc, method)
	if err != nil {
		return ""
	}

	text := strings.TrimSuffix(comments.Leading, "\n")
	if len(strings.TrimSpace(text)) == 0 {
		return ""
	}

	return text
}

// ProtoRelativeToPackage returns a type that is relative to the current file.
func ProtoRelativeToPackage(file *descriptor.FileDescriptorProto, typ string) string {
	if file.Package == nil {
		return typ
	}

	return strings.TrimPrefix(typ, fmt.Sprintf(".%s.", file.GetPackage()))
}
