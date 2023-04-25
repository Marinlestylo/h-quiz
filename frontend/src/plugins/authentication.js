/*
    This code comes from https://blog.devgenius.io/security-in-vuejs-3-0-with-authentication-and-authorization-by-keycloak-part-1-ae884889fa0d
*/ 
import Keycloak from "keycloak-js";

const initOptions = {
    url: 'http://127.0.0.1:8080/', realm: 'my-new-realm', clientId: 'vue-demo', onLoad: 'login-required'
}
const keycloakInstance = new Keycloak(initOptions);

const Login = (onAuthenticatedCallback) => {
    keycloakInstance
        .init({ onLoad: "login-required" })
        .then(function (authenticated) {
            authenticated ? onAuthenticatedCallback() : alert("non authenticated");
        })
        .catch((e) => {
            console.dir(e);
            console.log(`keycloak init exception: ${e}`);
        });
};

const KeyCloakService = {
    CallLogin: Login,
};

export default KeyCloakService;