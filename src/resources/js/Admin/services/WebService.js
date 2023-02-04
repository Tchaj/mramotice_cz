import Request from "./RequestService";

const WEB = "admin/web";

const obj = {
    /**shop detail */
    detail: async () => {
        try {
            const data = await Request.get(`${WEB}/info`, true);
            return data;
        } catch (e) {
            throw e;
        }
    },
    languages: async () => {
        try {
            const { data } = await Request.get("languages", true);
            return data;
        } catch (e) {
            throw e;
        }
    },
    dashboard: async () => {
        try {
            const { data } = await Request.get(`${WEB}/dashboard`, true);
            return data;
        } catch (e) {
            throw e;
        }
    }
};

export default obj;
