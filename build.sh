#!/bin/bash

# Add Node.js to PATH and run npm build
export PATH="/usr/local/bin:$PATH"

echo "Building assets with Vite..."
npm run build

echo "Removing hot file to ensure production build is used..."
rm -f public/hot

echo "Build completed successfully!"
echo "Assets are now available in public/build/"
echo "Manifest file: public/build/manifest.json"
