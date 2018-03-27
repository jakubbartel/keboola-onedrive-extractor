# Keboola OneDrive Extractor

[![Build Status](https://travis-ci.org/jakubbartel/keboola-onedrive-extractor.svg?branch=master)](https://travis-ci.org/jakubbartel/keboola-onedrive-extractor)

Extracts data from OneDrive

## Microsoft Graph API Application

1. Go to <https://apps.dev.microsoft.com/> (registration required)
2. Click "Add an app" button, fill in name of the new Application. Application id will be generated
3. To add *redirect urls* go to section Platforms / Add Platform / Web : fill in *Redirect URLs* and check *Allow implicit flow*
4. Generate application secret in section *Application secrets* button "Generate New Password" (save the secret)
5. Add Delegated permissions that oAuth can ask for: `Files.Read`, `Files.Read.All`, `Files.Read.Selected`, `Files.ReadWrite`, `Files.ReadWrite.All`, `Files.ReadWrite.Selected`
6. Fill other fields at your will
7. Hit "Save"

## OAuth

Use *Application Id*, *Secret* and *redict url* for OAuth with following links:

- oauth url: `https://login.microsoftonline.com/common/oauth2/v2.0/authorize?state=__state__&scope=offline_access%20Files.Read&response_type=code&approval_prompt=auto&redirect_uri=__redirect_uri__&client_id=__client_id__`
- token url: `https://login.microsoftonline.com/common/oauth2/v2.0/token`

## OAuth testing

Directory `/oauth` contains docker-compose file to run local OAuth testing environment - printing all OAuth data
returned from the server in browser.

1. `cd oauth`
2. `cp .env.example .env` open `.env` and fill all variables
3. `docker-compose up`
4. add redirect url `https://localhost:10200` to Microsoft Graph API Application settings
5. open web browser `https://localhost:10200` which should be redirected to Microsoft Login or print acquired access token
6. use `https://localhost:10200?refresh` to manually refresh actual access token

## Microsoft Graph API

Component uses Microsoft Graph API to connect to authenticated user's OneDrive. It downloads documents in three steps.

1. Create sharing link -- <https://developer.microsoft.com/en-us/graph/docs/api-reference/v1.0/api/shares_get> Transform a link
from component configuration into a *sharing link* which is something that can translate valid OneDrive/SharePoint document links
into unified format which then enables getting *OneDrive Item Id*. The reason for using sharing link is that Microsoft has multiple
services that allows to work with documents and getting *OneDrive Item Id* from each service manually would not be sustainable.
2. From sharing link the *OneDrive Item* can be obtained easily by using *shares* endpoint <https://developer.microsoft.com/en-us/graph/docs/api-reference/v1.0/api/shares_get#access-the-shared-item-directly>
3. Download content of the document using *oneDrive Item id* <https://docs.microsoft.com/en-us/onedrive/developer/rest-api/api/driveitem_get_content>

Sometime the *sharing url* cannot be obtained for documents that are shared to authenticated account. User should always be the owner
of the downloaded document.
