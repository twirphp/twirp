package php

import (
	"strings"

	"google.golang.org/protobuf/compiler/protogen"
	"google.golang.org/protobuf/reflect/protoreflect"
	"google.golang.org/protobuf/types/descriptorpb"
)

// Reserved PHP keywords that must be prefixed with something.
var reservedNames = []string{
	"abstract", "and", "array", "as", "break",
	"callable", "case", "catch", "class", "clone",
	"const", "continue", "declare", "default", "die",
	"do", "echo", "else", "elseif", "empty",
	"enddeclare", "endfor", "endforeach", "endif", "endswitch",
	"endwhile", "eval", "exit", "extends", "final",
	"for", "foreach", "function", "global", "goto",
	"if", "implements", "include", "include_once", "instanceof",
	"insteadof", "interface", "isset", "list", "namespace",
	"new", "or", "print", "private", "protected",
	"public", "require", "require_once", "return", "static",
	"switch", "throw", "trait", "try", "unset",
	"use", "var", "while", "xor", "int",
	"float", "bool", "string", "true", "false",
	"null", "void", "iterable",
}

// ClassNamePrefix calculates class name prefix.
//
// Created based on https://github.com/google/protobuf/blob/67952fab2c766ac5eacc15bb78e5af4039a3d398/src/google/protobuf/compiler/php/php_generator.cc#L137
func ClassNamePrefix(className string, file *protogen.File) string {
	return classNamePrefix(className, file.Desc)
}

func classNamePrefix(className string, file protoreflect.FileDescriptor) string {
	if options, ok := file.Options().(*descriptorpb.FileOptions); ok {
		prefix := options.GetPhpClassPrefix()
		if prefix != "" {
			return prefix
		}
	}

	// Check if the class name matches a reserved name
	isReserved := false
	for _, name := range reservedNames {
		if name == strings.ToLower(className) {
			isReserved = true

			break
		}
	}

	if isReserved {
		// Google internal classes receive a different prefix
		if file.Package() == "google.protobuf" {
			return "GPB"
		}

		return "PB"
	}

	return ""
}

// Namespace guesses the namespace of the file according to the following order of precedence:
//
// 1. Explicitly set namespace using the "php_namespace" option
// 2. Package name with dots replaced with backslashes and segments converted to title
func Namespace(file *protogen.File) string {
	return namespace(file.Desc)
}

func namespace(file protoreflect.FileDescriptor) string {
	if options, ok := file.Options().(*descriptorpb.FileOptions); ok {
		// When there is a namespace option defined we use it
		if options.PhpNamespace != nil {
			return options.GetPhpNamespace()
		}
	}

	return Name(string(file.Package()))
}

// Path guesses the path of the file based on the (internally calculated) namespace.
func Path(file *protogen.File) string {
	return strings.Replace(Namespace(file), "\\", "/", -1)
}

// PathFromNamespace guesses the path of the file based on the namespace.
func PathFromNamespace(ns string) string {
	return strings.Replace(ns, "\\", "/", -1)
}

// Name generates a name from a proto reference.
func Name(s string) string {
	parts := strings.Split(s, ".")

	for key, value := range parts {
		parts[key] = strings.Title(value)
	}

	return strings.Join(parts, "\\")
}

// NamespacedName calculates a fully qualified class name.
//
// Created based on https://github.com/google/protobuf/blob/67952fab2c766ac5eacc15bb78e5af4039a3d398/src/google/protobuf/compiler/php/php_generator.cc#L195
func NamespacedName(className string, file *protogen.File) string {
	return namespacedName(className, file.Desc)
}

func namespacedName(className string, file protoreflect.FileDescriptor) string {
	ns := namespace(file)

	if ns == "" {
		return className
	}

	return ns + "\\" + className
}

// ServiceName transforms the service name into a PHP compatible one.
func ServiceName(file *protogen.File, svc *protogen.Service) string {
	serviceName := string(svc.Desc.Name())

	return ClassNamePrefix(serviceName, file) + serviceName
}

// MessageName transforms a message name into a PHP compatible one.
func MessageName(file *protogen.File, message *protogen.Message) string {
	className := string(message.Desc.Name())
	parentFile := message.Desc.ParentFile()

	return "\\" + namespacedName(classNamePrefix(className, parentFile)+className, parentFile)
}
