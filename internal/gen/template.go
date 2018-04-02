package gen

import (
	"bytes"
	"errors"
	"strings"
	"text/template"

	"github.com/Masterminds/sprig"
	"github.com/twirphp/protoc-gen-twirp_php/php"
)

// TxtFuncMap wraps sprig.TxtFuncMap and adds some proto generation specific ones.
func TxtFuncMap(ctx *generatorContext) template.FuncMap {
	funcMap := sprig.TxtFuncMap()

	funcMap["eachTitle"] = eachFunc(strings.Title)

	funcMap["protoComment"] = ProtoComment

	funcMap["phpNamespace"] = php.Namespace
	funcMap["phpServiceName"] = php.ServiceName
	funcMap["phpMessageName"] = func(t string) (string, error) {
		msg := ctx.registry.MessageDefinition(t)
		if msg == nil {
			return "", errors.New("message definition not found for " + t)
		}

		return php.MessageName(msg), nil
	}

	return funcMap
}

// each applies a function to each string in a slice.
func each(s []string, fn func(string) string) []string {
	for key, value := range s {
		s[key] = fn(value)
	}

	return s
}

// eachFunc makes a simple string transformation function to an each function.
func eachFunc(fn func(string) string) func([]string) []string {
	return func(s []string) []string {
		return each(s, fn)
	}
}

// executeTemplate executes a template and returns the result.
func executeTemplate(ctx *generatorContext, t string, data interface{}) (string, error) {
	buf := new(bytes.Buffer)

	tpl, err := template.New("").Funcs(TxtFuncMap(ctx)).Parse(t)
	if err != nil {
		return "", err
	}

	err = tpl.Execute(buf, data)
	if err != nil {
		return "", err
	}

	return buf.String(), nil
}
