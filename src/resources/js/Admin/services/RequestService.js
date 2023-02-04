import Config from "../Config/App";
import axios from "axios";
const { apiUrl, headers } = Config;

const obj = {
    headers: headers,
    formHeaders: {
        "Content-Type": "application/x-www-form-urlencoded"
    },
    /** POST method */
    post: (url, data, auth = false) => {
        return new Promise((resolve, reject) => {
            return axios
                .post(
                    `${apiUrl}/${url}`,
                    { ...data },
                    {
                        headers: {
                            ...headers(auth)
                        }
                    }
                )
                .then(res => resolve(res.data))
                .catch(err => reject(err));
        });
    },
    /** POST method ~ custom headers */
    postH: (url, data, auth = false, addHeaders = {}) => {
        return new Promise((resolve, reject) => {
            return axios
                .post(`${apiUrl}/${url}`, data, {
                    headers: {
                        ...headers(auth),
                        ...addHeaders
                    }
                })
                .then(res => resolve(res.data))
                .catch(err => reject(err));
        });
    },
    /** GET method */
    get: (url, auth = false) => {
        return new Promise((resolve, reject) => {
            return axios
                .get(`${apiUrl}/${url}`, {
                    headers: {
                        ...headers(auth)
                    }
                })
                .then(res => resolve(res.data))
                .catch(err => reject(err));
        });
    },
    /** GET with blob type */
    getBlob: (url, auth) => {
        return new Promise((resolve, reject) => {
            return axios
                .get(`${apiUrl}/${url}`, {
                    headers: { ...headers(auth) },
                    responseType: "blob"
                })
                .then(res => resolve(res))
                .catch(err => reject(err));
        });
    },
    /** PUT method */
    put: (url, data, auth = false) => {
        return new Promise((resolve, reject) => {
            return axios
                .put(
                    `${apiUrl}/${url}`,
                    { ...data },
                    {
                        headers: {
                            ...headers(true)
                        }
                    }
                )
                .then(res => resolve(res.data))
                .catch(err => reject(err));
        });
    },
    /** PATCH method */
    patch: (url, data, auth = false) => {
        return new Promise((resolve, reject) => {
            return axios
                .patch(
                    `${apiUrl}/${url}`,
                    { ...data },
                    {
                        headers: {
                            ...headers(auth)
                        }
                    }
                )
                .then(res => resolve(res.data))
                .catch(err => reject(err));
        });
    },
    /** DELETE method */
    delete: (url, auth = false) => {
        return new Promise((resolve, reject) => {
            return axios
                .delete(`${apiUrl}/${url}`, {
                    headers: {
                        ...headers(auth)
                    }
                })
                .then(res => resolve(res.data))
                .catch(err => reject(err));
        });
    }
};

export default obj;
