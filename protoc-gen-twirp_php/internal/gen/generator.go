package gen

import (
	"fmt"
	"path"
	"strings"

	"github.com/gobuffalo/packr"
	"github.com/golang/protobuf/proto"
	"github.com/golang/protobuf/protoc-gen-go/descriptor"
	plugin "github.com/golang/protobuf/protoc-gen-go/plugin"
	"github.com/twirphp/twirp/protoc-gen-twirp_php/internal/php"
	"github.com/twitchtv/protogen/typemap"
)

const twirpVersion = "v5.3.0"

// Generator is code generator.
type Generator interface {
	// Generate generates the necessary files.
	Generate(*Request) (*plugin.CodeGeneratorResponse, error)
}

// Request contains all information necessary to start the code generation.
type Request struct {
	CodeGeneratorRequest *plugin.CodeGeneratorRequest

	// GlobalFiles are generated once per namespace.
	GlobalFiles []string

	// ServiceFiles are generated once per service with the service name as a prefix.
	ServiceFiles []string

	// Version is the compiler's version.
	Version string
}

// New creates a new generator instance.
func New(box packr.Box) Generator {
	return &generator{box}
}

type generator struct {
	box packr.Box
}

// generatorContext is passed around to every generation task.
type generatorContext struct {
	request  *Request
	registry *typemap.Registry
}

func (g *generator) Generate(req *Request) (*plugin.CodeGeneratorResponse, error) {
	ctx := &generatorContext{
		request:  req,
		registry: typemap.New(req.CodeGeneratorRequest.ProtoFile),
	}

	resp := &plugin.CodeGeneratorResponse{}

	namespaces := map[string]bool{}

	for _, file := range req.CodeGeneratorRequest.ProtoFile {
		namespaces[php.Namespace(file)] = true

		for _, svc := range file.Service {
			for _, serviceFile := range req.ServiceFiles {
				generatedFile, err := g.generateServiceFile(ctx, file, svc, serviceFile)
				if err != nil {
					return nil, err
				}

				resp.File = append(resp.File, generatedFile)
			}
		}
	}

	for namespace := range namespaces {
		for _, file := range req.GlobalFiles {
			generatedFile, err := g.generateGlobalFile(ctx, file, namespace)
			if err != nil {
				return nil, err
			}

			resp.File = append(resp.File, generatedFile)
		}
	}

	return resp, nil
}

type serviceFileData struct {
	File    *descriptor.FileDescriptorProto
	Service *descriptor.ServiceDescriptorProto
	Version string
}

func (g *generator) generateServiceFile(
	ctx *generatorContext,
	file *descriptor.FileDescriptorProto,
	svc *descriptor.ServiceDescriptorProto,
	serviceFile string,
) (*plugin.CodeGeneratorResponse_File, error) {
	data := &serviceFileData{
		File:    file,
		Service: svc,
		Version: ctx.request.Version,
	}

	tpl, err := g.box.MustString(serviceFile)
	if err != nil {
		return nil, err
	}

	tpl, err = executeTemplate(ctx, tpl, data)
	if err != nil {
		return nil, err
	}

	return &plugin.CodeGeneratorResponse_File{
		Name: proto.String(fmt.Sprintf(
			"%s/%s%s",
			php.Path(file),
			php.ServiceName(file, svc),
			strings.Replace(path.Base(serviceFile), "_Service_", "", -1),
		)),
		Content: proto.String(tpl),
	}, nil
}

type globalFileData struct {
	Namespace    string
	TwirpVersion string
	Version      string
}

func (g *generator) generateGlobalFile(ctx *generatorContext, file string, namespace string) (*plugin.CodeGeneratorResponse_File, error) {
	data := &globalFileData{
		Namespace:    namespace,
		TwirpVersion: twirpVersion,
		Version:      ctx.request.Version,
	}

	tpl, err := g.box.MustString(file)
	if err != nil {
		return nil, err
	}

	tpl, err = executeTemplate(ctx, tpl, data)
	if err != nil {
		return nil, err
	}

	return &plugin.CodeGeneratorResponse_File{
		Name:    proto.String(fmt.Sprintf("%s/%s", php.PathFromNamespace(namespace), path.Base(file))),
		Content: proto.String(tpl),
	}, nil
}
