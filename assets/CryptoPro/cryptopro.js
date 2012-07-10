var CADES_BES = 1;
var CADESCOM_CADES_X_LONG_TYPE_1 = 0x5d;
var CAPICOM_CURRENT_USER_STORE = 2;
var CAPICOM_MY_STORE = "My";
var CAPICOM_STORE_OPEN_MAXIMUM_ALLOWED = 2;
var CAPICOM_CERTIFICATE_FIND_SUBJECT_NAME = 1;

function GetErrorMessage(e) {
    var err = e.message;
    if (!err) {
        err = e;
    } else if (e.number) {
        err += " (" + e.number + ")";
    }
    return err;
}

function CreateObject(name) {
    switch (navigator.appName) {
        case "Microsoft Internet Explorer":
            return new ActiveXObject(name);
        default:
            var cadesobject = document.getElementById("cadesplugin");
	    var res = cadesobject.CreateObject(name);
	   return res;
    }
}

function SignCreate(certSubjectName, dataToSign) {
    var oStore = CreateObject("CAPICOM.Store");
    oStore.Open(CAPICOM_CURRENT_USER_STORE, CAPICOM_MY_STORE,
    CAPICOM_STORE_OPEN_MAXIMUM_ALLOWED);

    var oCertificates = oStore.Certificates.Find(
    CAPICOM_CERTIFICATE_FIND_SUBJECT_NAME, certSubjectName,true);
    if (oCertificates.Count == 0) {
        alert("Certificate not found");
        return;
    }
    var oCertificate = oCertificates.Item(1);
    var oSigner = CreateObject("CAdESCOM.CPSigner");
    oSigner.Certificate = oCertificate;
    oSigner.TSAAddress = "http://cryptopro.ru/tsp/";

    var oSignedData = CreateObject("CAdESCOM.CadesSignedData");
    oSignedData.ContentEncoding = 1;
    oSignedData.Content = dataToSign;

    try {
        var sSignedMessage = oSignedData.SignCades(oSigner, CADES_BES,true);
    } catch (err) {
        alert("Failed to create signature. Error: " + GetErrorMessage(err));
        return;
    }

    oStore.Close();

    return sSignedMessage;
}

function Verify(sSignedMessage) {
    var oSignedData = CreateObject("CAdESCOM.CadesSignedData");
    try {
        oSignedData.VerifyCades(sSignedMessage, CADESCOM_CADES_X_LONG_TYPE_1);
    } catch (err) {
        alert("Failed to verify signature. Error: " + GetErrorMessage(err));
        return false;
    }

    return true;
}