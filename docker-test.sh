#!/bin/bash

# Docker Build Test Script for MT5 Manager API
# This script tests different Docker build approaches to find the one that works

echo "🐳 MT5 Manager API Docker Build Test"
echo "======================================"
echo ""

# Check if Docker is available
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed or not in PATH"
    echo "   Please install Docker first: https://docs.docker.com/get-docker/"
    exit 1
fi

# Check if Docker is running
if ! docker info >/dev/null 2>&1; then
    echo "❌ Docker is not running"
    echo "   Please start Docker first"
    exit 1
fi

echo "✅ Docker is available and running"
echo ""

# Function to test build
test_build() {
    local dockerfile=$1
    local compose_file=$2
    local description=$3
    
    echo "🔧 Testing: $description"
    echo "   Dockerfile: $dockerfile"
    echo "   Compose file: $compose_file"
    echo ""
    
    if [ -n "$compose_file" ]; then
        # Test with docker-compose
        if docker-compose -f "$compose_file" build --no-cache >/dev/null 2>&1; then
            echo "✅ SUCCESS: $description works!"
            echo "   To use: docker-compose -f $compose_file up -d"
            return 0
        else
            echo "❌ FAILED: $description"
            return 1
        fi
    else
        # Test with direct docker build
        if docker build -f "$dockerfile" -t mt5-test . >/dev/null 2>&1; then
            echo "✅ SUCCESS: $description works!"
            echo "   To use: docker build -f $dockerfile -t mt5-manager-api ."
            docker rmi mt5-test >/dev/null 2>&1
            return 0
        else
            echo "❌ FAILED: $description"
            return 1
        fi
    fi
}

# Test different build approaches
echo "Testing different Docker build approaches..."
echo ""

# Test 1: Standard multi-stage build
if test_build "Dockerfile" "docker-compose.yml" "Standard multi-stage build"; then
    WORKING_BUILD="docker-compose.yml"
fi

echo ""

# Test 2: Simplified build
if test_build "Dockerfile.simple" "docker-compose.simple.yml" "Simplified single-stage build"; then
    WORKING_BUILD="docker-compose.simple.yml"
fi

echo ""

# Test 3: Minimal build
if test_build "Dockerfile.minimal" "docker-compose.minimal.yml" "Minimal build (essential extensions only)"; then
    WORKING_BUILD="docker-compose.minimal.yml"
fi

echo ""

# Test 4: Ultra-minimal build (curl + zip only)
if test_build "Dockerfile.ultra" "docker-compose.ultra.yml" "Ultra-minimal build (curl + zip only)"; then
    WORKING_BUILD="docker-compose.ultra.yml"
fi

echo ""

# Test 5: Direct simple build
if test_build "Dockerfile.simple" "" "Direct simple build"; then
    WORKING_BUILD="Dockerfile.simple"
fi

echo ""
echo "======================================"

if [ -n "$WORKING_BUILD" ]; then
    echo "🎉 SUCCESS! At least one build method works."
    echo ""
    echo "Recommended next steps:"
    
    if [[ "$WORKING_BUILD" == *"simple"* ]]; then
        echo "1. Use simplified setup:"
        echo "   cp .env.example .env"
        echo "   # Edit .env with your MT5 server details"
        echo "   docker-compose -f docker-compose.simple.yml up -d"
        echo ""
        echo "2. Or use Makefile:"
        echo "   make env-setup"
        echo "   make build-simple"
        echo "   make up-simple"
    else
        echo "1. Use standard setup:"
        echo "   cp .env.example .env"
        echo "   # Edit .env with your MT5 server details"
        echo "   docker-compose up -d"
        echo ""
        echo "2. Or use Makefile:"
        echo "   make setup"
    fi
    
    echo ""
    echo "3. Test with examples:"
    echo "   ./examples/docker_run_examples.sh basic"
    
else
    echo "❌ All build methods failed."
    echo ""
    echo "This might be due to:"
    echo "- Network connectivity issues"
    echo "- Docker configuration problems"
    echo "- System-specific package manager issues"
    echo ""
    echo "Try manual troubleshooting:"
    echo "1. Check Docker version: docker --version"
    echo "2. Check available space: df -h"
    echo "3. Try building with verbose output:"
    echo "   docker build -f Dockerfile.simple -t mt5-test ."
    echo ""
    echo "For more help, check the troubleshooting section in README.md"
fi

echo ""