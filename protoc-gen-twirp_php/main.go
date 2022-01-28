package main

import (
	"flag"
	"fmt"
	"io"
	"io/ioutil"
	"log"
	"os"

	"github.com/pkg/errors"
	"github.com/twirphp/twirp/protoc-gen-twirp_php/internal/gen"
	"google.golang.org/protobuf/compiler/protogen"
	"google.golang.org/protobuf/proto"
	"google.golang.org/protobuf/types/descriptorpb"
	"google.golang.org/protobuf/types/pluginpb"
)

// Provisioned by ldflags
var (
	version string
)

func main() {
	versionFlag := flag.Bool("version", false, "print version and exit")

	flag.Parse()

	if *versionFlag {
		fmt.Println(version)
		os.Exit(0)
	}

	err := Main(os.Stdin, os.Stdout)
	if err != nil {
		log.Fatal(err)
	}
}

// Main does the hard work. It is called by the main func.
func Main(in io.Reader, out io.Writer) error {
	req, err := readCodeGeneratorRequest(in)
	if err != nil {
		return err
	}

	// This is an ugly hack to make sure we bypass Go requirements
	for _, fd := range req.GetProtoFile() {
		pkg := "dummy/path"
		if fd.Options == nil {
			fd.Options = &descriptorpb.FileOptions{}
		}
		fd.Options.GoPackage = &pkg
	}

	options := protogen.Options{}

	plugin, err := options.New(req)
	if err != nil {
		return err
	}

	err = gen.Generate(plugin, version)
	if err != nil {
		plugin.Error(err)
	}

	err = writeCodeGeneratorResponse(out, plugin.Response())
	if err != nil {
		return err
	}

	return nil
}

func readCodeGeneratorRequest(in io.Reader) (*pluginpb.CodeGeneratorRequest, error) {
	data, err := ioutil.ReadAll(in)
	if err != nil {
		return nil, errors.Wrap(err, "cannot read input")
	}

	req := new(pluginpb.CodeGeneratorRequest)
	if err = proto.Unmarshal(data, req); err != nil {
		return nil, errors.Wrap(err, "cannot parse input proto")
	}

	if len(req.FileToGenerate) == 0 {
		return nil, errors.Wrap(err, "no files to generate")
	}

	return req, nil
}

func writeCodeGeneratorResponse(out io.Writer, resp *pluginpb.CodeGeneratorResponse) error {
	data, err := proto.Marshal(resp)
	if err != nil {
		return errors.Wrap(err, "cannot serialize output proto")
	}

	_, err = out.Write(data)
	if err != nil {
		return errors.Wrap(err, "cannot write output")
	}

	return nil
}
