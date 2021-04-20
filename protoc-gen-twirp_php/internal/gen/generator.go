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

var globalFiles = []string{
	"TwirpError.php",
}
var globalTemplates = template.Must(template.New("").Funcs(TxtFuncMap()).ParseFS(global.FS(), "*.php"))

var serviceFiles = []string{
	"_Service_.php",
	"AbstractClient.php",
	"Client.php",
	"JsonClient.php",
	"Server.php",
}
var serviceTemplates = template.Must(template.New("").Funcs(TxtFuncMap()).ParseFS(service.FS(), "*.php"))

// Generate is the main code generator.
func Generate(plugin *protogen.Plugin, version string) error {
	namespaces := map[string]bool{}

	for _, file := range plugin.Files {
		namespaces[php.Namespace(file)] = true

		for _, svc := range file.Services {
			for _, serviceFile := range serviceFiles {
				generatedFileName := fmt.Sprintf(
					"%s/%s%s",
					php.Path(file),
					php.ServiceName(file, svc),
					strings.Replace(serviceFile, "_Service_", "", -1),
				)

				generatedFile := plugin.NewGeneratedFile(generatedFileName, "dummy/path")

				data := &serviceFileData{
					File:         file,
					Service:      svc,
					TwirpVersion: twirpVersion,
					Version:      version,
				}

				err := serviceTemplates.ExecuteTemplate(generatedFile, serviceFile, data)
				if err != nil {
					return err
				}
			}
		}
	}

	for namespace := range namespaces {
		for _, file := range globalFiles {
			generatedFileName := fmt.Sprintf("%s/%s", php.PathFromNamespace(namespace), file)

			generatedFile := plugin.NewGeneratedFile(generatedFileName, "dummy/path")

			data := &globalFileData{
				Namespace: namespace,
				Version:   version,
			}

			err := globalTemplates.ExecuteTemplate(generatedFile, file, data)
			if err != nil {
				return err
			}
		}
	}

	return nil
}

func renderTemplate(plugin *protogen.Plugin, name string, fileName string, data interface{}) error {
	genFile := plugin.NewGeneratedFile(fileName, "")

	var tpl *template.Template

	return tpl.ExecuteTemplate(genFile, name, data)
}

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
