package gen

import (
	"fmt"
	"strings"
	"text/template"

	"github.com/twirphp/twirp/protoc-gen-twirp_php/internal/php"
	"github.com/twirphp/twirp/protoc-gen-twirp_php/templates/global"
	"github.com/twirphp/twirp/protoc-gen-twirp_php/templates/service"
	"google.golang.org/protobuf/compiler/protogen"
)

const twirpVersion = "v5.3.0"

var globalTemplates = template.Must(template.New("").Funcs(TxtFuncMap()).ParseFS(global.FS(), "*.php"))
var serviceTemplates = template.Must(template.New("").Funcs(TxtFuncMap()).ParseFS(service.FS(), "*.php"))

type serviceFileData struct {
	File         *protogen.File
	Service      *protogen.Service
	TwirpVersion string
	Version      string
}

type globalFileData struct {
	Namespace string
	Version   string
}

// Generate is the main code generator.
func Generate(plugin *protogen.Plugin, version string) error {
	namespaces := map[string]bool{}

	for _, file := range plugin.Files {
		namespaces[php.Namespace(file)] = true

		for _, svc := range file.Services {
			for _, tpl := range serviceTemplates.Templates() {
				fileName := fmt.Sprintf(
					"%s/%s%s",
					php.Path(file),
					php.ServiceName(file, svc),
					strings.Replace(tpl.Name(), "_Service_", "", -1),
				)

				generatedFile := plugin.NewGeneratedFile(fileName, "")

				data := &serviceFileData{
					File:         file,
					Service:      svc,
					TwirpVersion: twirpVersion,
					Version:      version,
				}

				err := tpl.Execute(generatedFile, data)
				if err != nil {
					return err
				}
			}
		}
	}

	for namespace := range namespaces {
		for _, tpl := range globalTemplates.Templates() {
			fileName := fmt.Sprintf("%s/%s", php.PathFromNamespace(namespace), tpl.Name())

			generatedFile := plugin.NewGeneratedFile(fileName, "")

			data := &globalFileData{
				Namespace: namespace,
				Version:   version,
			}

			err := tpl.Execute(generatedFile, data)
			if err != nil {
				return err
			}
		}
	}

	return nil
}
