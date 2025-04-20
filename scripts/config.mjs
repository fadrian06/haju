import { copyFileSync, existsSync, readFileSync, writeFileSync } from "node:fs";
import { parse } from "dotenv";
import { sassPlugin } from "esbuild-sass-plugin";

/** @type {import('esbuild').BuildOptions} */
export const commonOptions = {
  bundle: true,
  entryPoints: ["resources/src/layouts/*.ts", "resources/src/pages/*.ts"],
  format: "esm",
  jsx: "automatic",
  loader: {
    ".module.css": "local-css",
    ".ttf": "copy",
    ".woff": "copy",
    ".woff2": "copy",
    ".svg": "dataurl",
  },
  outdir: "resources/dist",
  target: ["es2018"],
  conditions: ["main"],
  plugins: [
    sassPlugin({
      quietDeps: true,
      silenceDeprecations: ["import"],
    }),
  ],
};

if (!existsSync(".env")) {
  copyFileSync(".env.dist", ".env");
}

const env = parse(readFileSync(".env"));
let declarations = "";

for (const variable in env) {
  env[variable] = `"${env[variable]}"`;
  declarations += `declare const ${variable}: string\n`;
}

writeFileSync("resources/src/env.d.ts", declarations);

export { env };
