# 🚀 How to Build Your Flutter APK with GitHub Actions

## Step 1: Upload Your Code to GitHub

1. **Create GitHub Account**: Go to https://github.com and sign up
2. **Create New Repository**: 
   - Click "New repository"
   - Name it `modern-form-app`
   - Make it public (free builds)
   - Check "Add a README file"
3. **Upload Your Files**:
   - Click "uploading an existing file"
   - Drag and drop ALL your Flutter files
   - Make sure the folder structure looks like this:
   \`\`\`
   your-repo/
   ├── .github/
   │   └── workflows/
   │       └── build-apk.yml
   ├── lib/
   │   └── main.dart
   ├── android/
   ├── backend/
   ├── pubspec.yaml
   └── README.md
   \`\`\`

## Step 2: Automatic Build Process

Once you upload the workflow file (`.github/workflows/build-apk.yml`), GitHub will:

1. ✅ **Automatically detect** your Flutter project
2. ✅ **Install Flutter SDK** and dependencies
3. ✅ **Run tests** and code analysis
4. ✅ **Build APK files** (both debug and release)
5. ✅ **Create downloadable artifacts**
6. ✅ **Make a GitHub release** with your APK

## Step 3: Download Your APK

### Method 1: From Actions Tab
1. Go to your repository on GitHub
2. Click "Actions" tab
3. Click on the latest build (green checkmark)
4. Scroll down to "Artifacts" section
5. Download `app-release-apk`

### Method 2: From Releases
1. Go to your repository
2. Click "Releases" on the right side
3. Download the APK from the latest release

## Step 4: Install on Your Phone

1. **Download APK** to your Android device
2. **Enable Unknown Sources**:
   - Settings → Security → Unknown Sources (ON)
   - Or Settings → Apps → Special Access → Install Unknown Apps
3. **Install APK**: Tap the downloaded file and install

## Build Status

The workflow will show you:
- ✅ **Build Success**: Green checkmark = APK ready
- ❌ **Build Failed**: Red X = Check the logs for errors
- 🟡 **Building**: Yellow circle = Currently building

## File Sizes

Your APK will be approximately:
- **Release APK**: 15-25 MB (optimized)
- **Debug APK**: 25-35 MB (with debug info)
- **Split APKs**: 8-15 MB each (architecture-specific)

## Troubleshooting

### Build Fails?
1. Check the "Actions" tab for error logs
2. Common issues:
   - Missing `pubspec.yaml`
   - Incorrect folder structure
   - Syntax errors in Dart code

### APK Won't Install?
1. Make sure "Unknown Sources" is enabled
2. Try the debug APK if release fails
3. Check Android version compatibility (min API 21)

## Advanced Features

The workflow also creates:
- **App Bundle (AAB)**: For Google Play Store
- **Split APKs**: Smaller files for specific devices
- **Automatic Releases**: Tagged versions with changelogs
- **iOS Build**: (Optional, requires macOS runner)

## Cost

- **GitHub Actions**: FREE for public repositories
- **Build Time**: ~5-10 minutes per build
- **Storage**: Artifacts kept for 30 days

## Next Steps

After your first successful build:
1. ✅ Test the APK on your device
2. ✅ Update your server URL in the code
3. ✅ Set up your PHP backend
4. ✅ Share your app with others!

---

**Need Help?** Check the Actions logs or create an issue in your repository.
