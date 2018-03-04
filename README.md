# Keboola OneDrive Extractor

Extracts data from OneDrive

## Microsoft Graph API Application

1. Go to <https://apps.dev.microsoft.com/> (registration required)
2. Click "Add an app" button, fill in name of the new Application. Application id will be generated
3. To add *redirect urls* go to section Platforms / Add Platform / Web : fill in *Redirect URLs* and check *Allow implicit flow*
4. Generate application secret in section *Application secrets* button "Generate New Password" (save the secret)
5. Add permissions that oAuth can ask for: `Files.Read`, `Files.Read.All`, `Files.Read.Selected`, `Files.ReadWrite`, `Files.ReadWrite.All`, `Files.ReadWrite.Selected`
6. Fill other fields at your will
7. Hit "Save"

## OAuth

Use *Application Id*, *Secret* and *redict url* for OAuth with following links:

- oauth url: `https://login.microsoftonline.com/common/oauth2/v2.0/authorize?state=__state__&scope=offline_access%20Files.Read&response_type=code&approval_prompt=auto&redirect_uri=__redirect_uri__&client_id=__client_id__`
- token url: `https://login.microsoftonline.com/common/oauth2/v2.0/token`
