package proto

import (
	"strconv"
	"strings"

	"github.com/golang/protobuf/protoc-gen-go/descriptor"
	"github.com/pkg/errors"
	"github.com/twitchtv/protogen/typemap"
)

// Comment is a template wrapper for protocol buffer descriptor comments.
func Comment(desc ...interface{}) (string, error) {
	switch  len(desc) {
	case 2:
		file := desc[0].(*descriptor.FileDescriptorProto)
		svc := desc[1].(*descriptor.ServiceDescriptorProto)

		return ServiceComment(file, svc), nil

	case 3:
		file := desc[0].(*descriptor.FileDescriptorProto)
		svc := desc[1].(*descriptor.ServiceDescriptorProto)
		method := desc[2].(*descriptor.MethodDescriptorProto)

		return MethodComment(file, svc, method), nil

	default:
		return "", errors.New("unexpected amount of arguments (expected 2 or 3, got " + strconv.Itoa(len(desc)))
	}
}

// ServiceComment extracts comments for a service.
func ServiceComment(file *descriptor.FileDescriptorProto, svc *descriptor.ServiceDescriptorProto) string {
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

// MethodComment extracts comments for a service method.
func MethodComment(file *descriptor.FileDescriptorProto, svc *descriptor.ServiceDescriptorProto, method *descriptor.MethodDescriptorProto) string {
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
