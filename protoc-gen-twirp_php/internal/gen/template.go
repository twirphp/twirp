package gen

import (
	"text/template"

	"github.com/Masterminds/sprig/v3"
	"github.com/twirphp/twirp/protoc-gen-twirp_php/internal/php"
	"github.com/twirphp/twirp/protoc-gen-twirp_php/internal/proto"
)

// TxtFuncMap wraps sprig.TxtFuncMap and adds some proto generation specific ones.
func TxtFuncMap() template.FuncMap {
	funcMap := sprig.TxtFuncMap()

	funcMap["protoMethodFullName"] = proto.MethodFullName
	funcMap["protoSplitComments"] = proto.SplitComments

	funcMap["phpNamespace"] = php.Namespace
	funcMap["phpServiceName"] = php.ServiceName
	funcMap["phpMessageName"] = php.MessageName

	return funcMap
}
