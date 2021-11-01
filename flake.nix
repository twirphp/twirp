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
      in {
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
