
# ScarPay

## Accès à la page de configuration

Rendez-vous sur https://unittest-fabiensebban.c9users.io/ (https://unittest-fabiensebban.c9users.io/) pour accéder à la page de configuration.
Si le serverur ne répond pas, vous pouvez me contacter au 06 60 94 14 78

## Remplissage des informations

Veuillez saisir toutes vos informations concernant votre compte: 

- UserID : permet de vous indentifiez lorsque vous utilisez les API
- App ID de Paypal: Utilisez l'APP ID de la sandbox
- API User de Paypal: fourni pas Paypal
- API Password de Paypal: fourni pas Paypal
- API Signature de Paypal: fourni pas Paypal

## API ScarPay

/Pay [POST]
POST params :
- UserId
- ReturnUrl
- CancelUrl
- MarketPlaceEmail
- MerchantEmail
- AmountMarketPlace
- AmountMerchant

Response:
  "error": (boolean),
  "errorMessage": (if error),
  "url": ((if !error) Pay URL),
  "payKey": (Paypal key)

/Refund [POST]
- payKey

Response
  "error": (boolean),
  "errorMessage": (if error),
  "url": ((if !error) Pay URL),
  "payKey": (Paypal key)

## Code Ruby à insérer

Après avoir valider le formulaire, vous trouverez une page expliquant le code Ruby à insérer dans votre projet Ruby


## License

This is an open source project
