package gen

import (
	"bytes"
	"errors"
	"text/template"

	"github.com/Masterminds/sprig"
	"github.com/twirphp/twirp/protoc-gen-twirp_php/internal/php"
	"github.com/twirphp/twirp/protoc-gen-twirp_php/internal/proto"
)

// TxtFuncMap wraps sprig.TxtFuncMap and adds some proto generation specific ones.
func TxtFuncMap(ctx *generatorContext) template.FuncMap {
	funcMap := sprig.TxtFuncMap()

	funcMap["protoComment"] = proto.Comment
	funcMap["protoFullName"] = proto.FullName

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
