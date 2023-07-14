export const backUrl = import.meta.env.VITE_BACKEND_URL;
export const appUrl = import.meta.env.VITE_APP_URL;

export const fetchApi = async (uri, settings) => {
    const url = new URL(uri, backUrl);
    const response = await fetch(url, { ...settings, credentials: 'include' });
    return response;
}