{
  description = "TwirPHP: PHP port of Twitch's Twirp RPC framework";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixpkgs-unstable";
    flake-parts.url = "github:hercules-ci/flake-parts";
    devenv.url = "github:cachix/devenv";
    phps = {
      url = "github:fossar/nix-phps";
      inputs.nixpkgs.follows = "nixpkgs";
    };
  };

  outputs = inputs@{ flake-parts, ... }:
    flake-parts.lib.mkFlake { inherit inputs; } {
      imports = [
        inputs.devenv.flakeModule
      ];

      systems = [ "x86_64-linux" "x86_64-darwin" "aarch64-darwin" ];

      perSystem = { config, self', inputs', pkgs, system, ... }: rec {
        devenv.shells = {
          default = {
            languages = {
              go.enable = true;

              php = {
                enable = true;
                extensions = [ "xdebug" ];
              };
            };

            pre-commit.hooks = {
              nixpkgs-fmt.enable = true;
              yamllint.enable = true;
            };

            packages = with pkgs; [
              gnumake

              protobuf
              gotestsum

              golangci-lint
              php.packages.phpstan
              php.packages.php-cs-fixer
              php.packages.psalm

              goreleaser

              yamllint
            ] ++ [
              self'.packages.clientcompat
            ];

            enterShell = ''
              export PATH="$PWD/$(composer config vendor-dir)/bin:$PATH"
            '';

            # https://github.com/cachix/devenv/issues/528#issuecomment-1556108767
            containers = pkgs.lib.mkForce { };
          };

          ci = devenv.shells.default;

          ci_7_3 = {
            imports = [ devenv.shells.default ];

            languages = {
              php = {
                version = "7.3";
              };
            };
          };

          ci_7_4 = {
            imports = [ devenv.shells.default ];

            languages = {
              php = {
                version = "7.4";
              };
            };
          };

          ci_8_0 = {
            imports = [ devenv.shells.default ];

            languages = {
              php = {
                version = "8.0";
              };
            };
          };

          ci_8_1 = {
            imports = [ devenv.shells.default ];

            languages = {
              php = {
                version = "8.1";
              };
            };
          };

          ci_8_2 = {
            imports = [ devenv.shells.default ];

            languages = {
              php = {
                version = "8.2";
              };
            };
          };
        };

        packages = {
          clientcompat = pkgs.buildGoPackage rec {
            pname = "clientcompat";
            version = "8.1.3";

            goPackagePath = "github.com/twitchtv/twirp";

            src = pkgs.fetchFromGitHub {
              owner = "twitchtv";
              repo = "twirp";
              rev = "v${version}";
              sha256 = "sha256-p3gHVHGBHakOOQnJAuMK7vZumNXN15mOABuEHUG0wNs=";
            };

            subPackages = [ "clientcompat" ];
          };

          protoc-gen-twirp_php = pkgs.buildGoModule rec {
            pname = "protoc-gen-twirp_php";
            version = "0.9.1";

            src = ./.;

            vendorSha256 = "sha256-Kz9tMM4XSMOUmlHb/BE5/C/ZohdE505DTeDj9lGki/I=";

            subPackages = [ "protoc-gen-twirp_php" ];

            ldflags = [
              "-w"
              "-s"
              "-X main.version=v${version}"
            ];
          };

          default = packages.protoc-gen-twirp_php;
        };
      };
    };
}
