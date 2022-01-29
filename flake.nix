{
  description = "TwirPHP: PHP port of Twitch's Twirp RPC framework";

  inputs = {
    nixpkgs.url = "nixpkgs/nixos-unstable";
    flake-utils.url = "github:numtide/flake-utils";
    flake-compat = {
      url = "github:edolstra/flake-compat";
      flake = false;
    };
  };

  outputs = { self, nixpkgs, flake-utils, ... }:
    flake-utils.lib.eachDefaultSystem (system:
      let
        pkgs = import nixpkgs { inherit system; };
        clientcompat = pkgs.buildGoPackage rec {
          pname = "clientcompat";
          version = "8.1.0";

          goPackagePath = "github.com/twitchtv/twirp";

          src = pkgs.fetchFromGitHub {
            owner = "twitchtv";
            repo = "twirp";
            rev = "v${version}";
            sha256 = "ezSNrDfOE1nj4FlX7E7Z7/eGfQw1B7NP34aj8ml5pDk=";
          };

          subPackages = [ "clientcompat" ];
        };
      in
      rec
      {
        defaultPackage = packages.protoc-gen-twirp_php;

        packages.protoc-gen-twirp_php = pkgs.buildGoModule rec {
          pname = "protoc-gen-twirp_php";
          version = "0.8.1";

          src = ./.;

          vendorSha256 = "sha256-z3Yp+Yy03g2DAvWUZXaOxQWONjnYUl69eTpYIDPhsqc=";

          subPackages = [ "protoc-gen-twirp_php" ];

          ldflags = [
            "-w"
            "-s"
            "-X main.version=v${version}"
          ];
        };

        devShell = pkgs.mkShell {
          buildInputs = with pkgs;
            [
              git
              gnumake
              go
              (php.withExtensions ({ enabled, all }: enabled ++ [ all.xdebug ]))
              protobuf
              php.packages.composer
              golangci-lint
              gotestsum
              goreleaser
            ] ++ [ clientcompat ];
        };
      });
}
