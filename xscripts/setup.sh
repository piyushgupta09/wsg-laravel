#!/bin/bash

# Get the directory where the script is located
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
echo "Script directory: $SCRIPT_DIR"

# Source the package-list.sh using the relative path
SOURCE_FILE="$SCRIPT_DIR/package-list.sh"

echo "Starting main script..."

# Check if package-list.sh exists
if [ -f "$SOURCE_FILE" ]; then
    echo "Found package-list.sh, sourcing it now..."
    source "$SOURCE_FILE"
else
    echo "Error: package-list.sh not found."
    exit 1
fi

# Function to check if a command exists
command_exists() {
    type "$1" &> /dev/null 
}

echo "Hello, welcome to the script!"

# Check if Composer is installed
if command_exists composer; then
    echo "Composer is installed, proceeding with PHP packages installation..."
else
    echo "Error: Composer is not installed. Please install Composer to proceed."
    exit 1
fi

# Check if NPM is installed
if command_exists npm; then
    echo "NPM is installed, proceeding with Node packages installation..."
else
    echo "Error: NPM is not installed. Please install NPM to proceed."
    exit 1
fi

# Installing PHP packages with Composer
echo "Installing PHP packages with Composer..."
for package in "${phpPackages[@]}"; do
    PACKAGE_NAME=$(echo "$package" | cut -d ':' -f 1)
    if grep -q "\"$PACKAGE_NAME\"" composer.json; then
        echo "$PACKAGE_NAME is already listed in composer.json"
    else
        echo "Installing $package..."
        composer require "$package" || {
            echo "Failed to install $package"
            exit 1
        }
    fi
done

# Installing development PHP packages with Composer
echo "Installing development PHP packages with Composer..."
for package in "${devPhpPackages[@]}"; do
    PACKAGE_NAME=$(echo "$package" | cut -d ':' -f 1)
    if grep -q "\"$PACKAGE_NAME\"" composer.json; then
        echo "$PACKAGE_NAME is already listed in composer.json"
    else
        echo "Installing $package..."
        composer require "$package" --dev || {
            echo "Failed to install $package"
            exit 1
        }
    fi
done

# Installing Node packages with NPM
echo "Installing Node packages with NPM..."
for package in "${nodePackages[@]}"; do
    PACKAGE_NAME=$(echo "$package" | cut -d '@' -f 1)
    if grep -q "\"$PACKAGE_NAME\"" package.json; then
        echo "$PACKAGE_NAME is already listed in package.json"
    else
        echo "Installing $package..."
        npm install "$package" || {
            echo "Failed to install $package"
            exit 1
        }
    fi
done

# Running setup commands
echo "Running setup commands..."
for command in "${setupCommands[@]}"; do
    echo "Running $command..."
    eval "$command" || {
        echo "Failed to run $command"
        exit 1
    }
    sleep 1  # Pause for 1 second
done

echo "Script finished."
