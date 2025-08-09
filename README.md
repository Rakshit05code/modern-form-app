# Modern Form App

A beautiful Flutter registration app with PHP backend integration.

## Features

- Modern dark theme UI with gradient backgrounds
- Form validation and error handling
- PHP backend with MySQL database
- View all submissions functionality
- Responsive design with animations

## Setup Instructions

### Prerequisites

1. **Flutter SDK** (3.0.0 or higher)
2. **Android Studio** or **VS Code** with Flutter extensions
3. **PHP** (7.4 or higher)
4. **MySQL** database
5. **Web server** (Apache/Nginx) or **XAMPP/WAMP**

### Flutter App Setup

1. Download and extract the project files
2. Open terminal in the project directory
3. Run: `flutter pub get`
4. Update the `baseUrl` in `lib/main.dart` with your server URL
5. Connect your Android device or start an emulator
6. Run: `flutter run`

### Backend Setup

1. Copy the `backend/` folder to your web server directory
2. Create a MySQL database named `flutter_app`
3. Update database credentials in `backend/db.php`
4. The table will be created automatically when you first run the app

### Building APK

To build a release APK:

\`\`\`bash
flutter build apk --release
\`\`\`

The APK will be generated in: `build/app/outputs/flutter-apk/app-release.apk`

For a smaller APK, build split APKs:

\`\`\`bash
flutter build apk --split-per-abi
\`\`\`

## API Endpoints

- `POST /insert.php` - Create new submission
- `GET /fetch.php` - Get all submissions with pagination

## Database Schema

\`\`\`sql
CREATE TABLE submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    dob DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
\`\`\`

## Troubleshooting

1. **Network Error**: Make sure your server URL is correct and accessible
2. **Database Connection**: Check database credentials in `db.php`
3. **CORS Issues**: The PHP files include CORS headers for cross-origin requests
4. **Build Issues**: Run `flutter clean` then `flutter pub get`

## License

This project is open source and available under the MIT License.
\`\`\`

```gradle file="android/app/build.gradle"
def localProperties = new Properties()
def localPropertiesFile = rootProject.file('local.properties')
if (localPropertiesFile.exists()) {
    localPropertiesFile.withReader('UTF-8') { reader ->
        localProperties.load(reader)
    }
}

def flutterRoot = localProperties.getProperty('flutter.sdk')
if (flutterRoot == null) {
    throw new GradleException("Flutter SDK not found. Define location with flutter.sdk in the local.properties file.")
}

def flutterVersionCode = localProperties.getProperty('flutter.versionCode')
if (flutterVersionCode == null) {
    flutterVersionCode = '1'
}

def flutterVersionName = localProperties.getProperty('flutter.versionName')
if (flutterVersionName == null) {
    flutterVersionName = '1.0'
}

apply plugin: 'com.android.application'
apply plugin: 'kotlin-android'
apply from: "$flutterRoot/packages/flutter_tools/gradle/flutter.gradle"

android {
    namespace "com.example.modern_form_app"
    compileSdkVersion flutter.compileSdkVersion
    ndkVersion flutter.ndkVersion

    compileOptions {
        sourceCompatibility JavaVersion.VERSION_1_8
        targetCompatibility JavaVersion.VERSION_1_8
    }

    kotlinOptions {
        jvmTarget = '1.8'
    }

    sourceSets {
        main.java.srcDirs += 'src/main/kotlin'
    }

    defaultConfig {
        applicationId "com.example.modern_form_app"
        minSdkVersion flutter.minSdkVersion
        targetSdkVersion flutter.targetSdkVersion
        versionCode flutterVersionCode.toInteger()
        versionName flutterVersionName
    }

    buildTypes {
        release {
            signingConfig signingConfigs.debug
        }
    }
}

flutter {
    source '../..'
}

dependencies {
    implementation "org.jetbrains.kotlin:kotlin-stdlib-jdk7:$kotlin_version"
}
