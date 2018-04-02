package main

import (
	"flag"
	"fmt"
	"io"
	"io/ioutil"
	"log"
	"os"

	"github.com/gobuffalo/packr"
	"github.com/golang/protobuf/proto"
	plugin "github.com/golang/protobuf/protoc-gen-go/plugin"
	"github.com/pkg/errors"
	"github.com/twirphp/protoc-gen-twirp_php/internal/gen"
)

// Provisioned by ldflags
var (
	Version string
)

func main() {
	version := flag.Bool("version", false, "print version and exit")

	flag.Parse()

	if *version {
		fmt.Println(Version)
		os.Exit(0)
	}

	err := Main(os.Stdin, os.Stdout, "./templates")
	if err != nil {
		log.Fatal(err)
	}
}

// Main does the hard work. It is called by the main func.
func Main(in io.Reader, out io.Writer, templates string) error {
	req, err := readCodeGeneratorRequest(in)
	if err != nil {
		return err
	}

	box := packr.NewBox(templates)
	g := gen.New(box)

	greq := &gen.Request{
		CodeGeneratorRequest: req,
		GlobalFiles: []string{
			"global/Server.php",
			"global/TwirpServer.php",
			"global/TwirpClient.php",
		},
		ServiceFiles: []string{
			"service/_Service_.php",
			"service/Client.php",
			"service/Server.php",
		},
	}

	resp, err := g.Generate(greq)
	if err != nil {
		return err
	}

	err = writeCodeGeneratorResponse(out, resp)
	if err != nil {
		return err
	}

	return nil
}

func readCodeGeneratorRequest(in io.Reader) (*plugin.CodeGeneratorRequest, error) {
	data, err := ioutil.ReadAll(in)
	if err != nil {
		return nil, errors.Wrap(err, "cannot read input")
	}

	req := new(plugin.CodeGeneratorRequest)
	if err = proto.Unmarshal(data, req); err != nil {
		return nil, errors.Wrap(err, "cannot parse input proto")
	}

	if len(req.FileToGenerate) == 0 {
		return nil, errors.Wrap(err, "no files to generate")
	}

	return req, nil
}

func writeCodeGeneratorResponse(out io.Writer, resp *plugin.CodeGeneratorResponse) error {

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
