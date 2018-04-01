package main

import (
	"strings"

	"github.com/golang/protobuf/protoc-gen-go/descriptor"
	"github.com/twitchtv/protogen/typemap"
)

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
