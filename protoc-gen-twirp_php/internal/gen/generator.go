package gen

import (
	"fmt"
	"io/fs"
	"path"
	"strings"

	"github.com/twirphp/twirp/protoc-gen-twirp_php/internal/php"
	"google.golang.org/protobuf/compiler/protogen"
	"google.golang.org/protobuf/proto"
	"google.golang.org/protobuf/reflect/protoreflect"
	"google.golang.org/protobuf/types/descriptorpb"
	"google.golang.org/protobuf/types/pluginpb"
)

const twirpVersion = "v5.3.0"

// Generator is code generator.
type Generator interface {
	// Generate generates the necessary files.
	Generate(*Request) (*pluginpb.CodeGeneratorResponse, error)
}

// Request contains all information necessary to start the code generation.
type Request struct {
	CodeGeneratorRequest *pluginpb.CodeGeneratorRequest

	// GlobalFiles are generated once per namespace.
	GlobalFiles []string

	// ServiceFiles are generated once per service with the service name as a prefix.
	ServiceFiles []string

	// Version is the compiler's version.
	Version string
}

// New creates a new generator instance.
func New(fsys fs.FS) Generator {
	return &generator{fsys}
}

type generator struct {
	fsys fs.FS
}

// generatorContext is passed around to every generation task.
type generatorContext struct {
	request    *Request
	fileReg    map[protoreflect.FullName]*protogen.File
	messageReg map[protoreflect.FullName]*protogen.Message
}

func (g *generator) Generate(req *Request) (*pluginpb.CodeGeneratorResponse, error) {
	plugin, err := protogen.Options{}.New(req.CodeGeneratorRequest)
	if err != nil {
		return nil, err
	}

	fileReg := make(map[protoreflect.FullName]*protogen.File)
	messageReg := make(map[protoreflect.FullName]*protogen.Message)

	for _, f := range plugin.Files {
		for _, m := range f.Messages {
			fileReg[m.Desc.FullName()] = f
			messageReg[m.Desc.FullName()] = m
		}
	}

	ctx := &generatorContext{
		request:    req,
		fileReg:    fileReg,
		messageReg: messageReg,
	}

	resp := &pluginpb.CodeGeneratorResponse{}

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
	File         *descriptorpb.FileDescriptorProto
	Service      *descriptorpb.ServiceDescriptorProto
	TwirpVersion string
	Version      string
}

func (g *generator) generateServiceFile(
	ctx *generatorContext,
	file *descriptorpb.FileDescriptorProto,
	svc *descriptorpb.ServiceDescriptorProto,
	serviceFile string,
) (*pluginpb.CodeGeneratorResponse_File, error) {
	data := &serviceFileData{
		File:         file,
		Service:      svc,
		TwirpVersion: twirpVersion,
		Version:      ctx.request.Version,
	}

	tplContent, err := fs.ReadFile(g.fsys, serviceFile)
	if err != nil {
		return nil, err
	}

	tpl, err := executeTemplate(ctx, string(tplContent), data)
	if err != nil {
		return nil, err
	}

	return &pluginpb.CodeGeneratorResponse_File{
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
	Namespace string
	Version   string
}

func (g *generator) generateGlobalFile(ctx *generatorContext, file string, namespace string) (*pluginpb.CodeGeneratorResponse_File, error) {
	data := &globalFileData{
		Namespace: namespace,
		Version:   ctx.request.Version,
	}

	tplContent, err := fs.ReadFile(g.fsys, file)
	if err != nil {
		return nil, err
	}

	tpl, err := executeTemplate(ctx, string(tplContent), data)
	if err != nil {
		return nil, err
	}

	return &pluginpb.CodeGeneratorResponse_File{
		Name:    proto.String(fmt.Sprintf("%s/%s", php.PathFromNamespace(namespace), path.Base(file))),
		Content: proto.String(tpl),
	}, nil
}
