package main

import (
	"fmt"

	"github.com/gobuffalo/packr"
	"github.com/golang/protobuf/proto"
	"github.com/golang/protobuf/protoc-gen-go/descriptor"
	plugin "github.com/golang/protobuf/protoc-gen-go/plugin"
)

type generator struct {
	box packr.Box
}

func newGenerator(box packr.Box) *generator {
	return &generator{box}
}

func (g *generator) generate(in *plugin.CodeGeneratorRequest) (*plugin.CodeGeneratorResponse, error) {
	resp := &plugin.CodeGeneratorResponse{}

	namespaces := map[string]bool{}

	for _, file := range in.ProtoFile {
		namespaces[PhpNamespace(file)] = true

		for _, svc := range file.Service {
			serviceInterfaceFile, err := g.generateServiceInterface(file, svc)
			if err != nil {
				return nil, err
			}

			serviceServerFile, err := g.generateServiceServer(file, svc)
			if err != nil {
				return nil, err
			}

			resp.File = append(resp.File, serviceInterfaceFile, serviceServerFile)
		}
	}

	for namespace := range namespaces {
		protocolFile, err := g.generateProtocol(namespace)
		if err != nil {
			return nil, err
		}

		serverFile, err := g.generateServer(namespace)
		if err != nil {
			return nil, err
		}

		resp.File = append(resp.File, protocolFile, serverFile)
	}

	return resp, nil
}

type serviceDefinition struct {
	File    *descriptor.FileDescriptorProto
	Service *descriptor.ServiceDescriptorProto
}

func (g *generator) generateServiceInterface(file *descriptor.FileDescriptorProto, svc *descriptor.ServiceDescriptorProto) (*plugin.CodeGeneratorResponse_File, error) {
	data := &serviceDefinition{
		File:    file,
		Service: svc,
	}

	tpl, err := g.box.MustString("ServiceInterface.php")
	if err != nil {
		return nil, err
	}

	tpl, err = executeTemplate(tpl, data)
	if err != nil {
		return nil, err
	}

	return &plugin.CodeGeneratorResponse_File{
		Name:    proto.String(fmt.Sprintf("%s/%s.php", PhpPath(file), PhpServiceName(svc))),
		Content: proto.String(tpl),
	}, nil
}

func (g *generator) generateServiceServer(file *descriptor.FileDescriptorProto, svc *descriptor.ServiceDescriptorProto) (*plugin.CodeGeneratorResponse_File, error) {
	data := &serviceDefinition{
		File:    file,
		Service: svc,
	}

	tpl, err := g.box.MustString("ServiceServer.php")
	if err != nil {
		return nil, err
	}

	tpl, err = executeTemplate(tpl, data)
	if err != nil {
		return nil, err
	}

	return &plugin.CodeGeneratorResponse_File{
		Name:    proto.String(fmt.Sprintf("%s/%sServer.php", PhpPath(file), PhpServiceName(svc))),
		Content: proto.String(tpl),
	}, nil
}

type protocolDefinition struct {
	Namespace string
}

func (g *generator) generateProtocol(namespace string) (*plugin.CodeGeneratorResponse_File, error) {
	data := &protocolDefinition{
		Namespace: namespace,
	}

	tpl, err := g.box.MustString("Protocol.php")
	if err != nil {
		return nil, err
	}

	tpl, err = executeTemplate(tpl, data)
	if err != nil {
		return nil, err
	}

	return &plugin.CodeGeneratorResponse_File{
		Name:    proto.String(fmt.Sprintf("%s/Protocol.php", PhpPathFromNamespace(namespace))),
		Content: proto.String(tpl),
	}, nil
}

func (g *generator) generateServer(namespace string) (*plugin.CodeGeneratorResponse_File, error) {
	data := &protocolDefinition{
		Namespace: namespace,
	}

	tpl, err := g.box.MustString("Server.php")
	if err != nil {
		return nil, err
	}

	tpl, err = executeTemplate(tpl, data)
	if err != nil {
		return nil, err
	}

	return &plugin.CodeGeneratorResponse_File{
		Name:    proto.String(fmt.Sprintf("%s/Server.php", PhpPathFromNamespace(namespace))),
		Content: proto.String(tpl),
	}, nil
}
