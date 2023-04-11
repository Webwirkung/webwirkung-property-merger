# Documentation
tbd

## Generate installable ZIP file
To generate the ZIP file, execute one level above the git folder:
```shell
zip -r WebwirkungPropertyMerger.zip WebwirkungPropertyMerger -x "WebwirkungPropertyMerger/vendor*" -x "WebwirkungPropertyMerger/.*" -x "WebwirkungPropertyMerger/__MACOSX" -x "**/.*"
```
