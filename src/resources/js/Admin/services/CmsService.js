import Request from "./RequestService";

const CMS = "admin/cms";

const obj = {
    /**list */
    list: async () => {
        try {
            const { data } = await Request.get(CMS, true);
            return data;
        } catch (e) {
            throw e;
        }
    },
    /**detail */
    detail: async id => {
        try {
            const { data } = await Request.get(`${CMS}/${id}`, true);
            return data;
        } catch (e) {
            throw e;
        }
    },
    /**create */
    create: async data => {
        try {
            const response = await Request.post(CMS, data, true);
            return response;
        } catch (e) {
            throw e;
        }
    },
    /**update by id */
    update: async (id, data) => {
        try {
            const response = await Request.patch(`${CMS}/${id}`, data, true);
            return response;
        } catch (e) {
            throw e;
        }
    },
    /**remove by id */
    remove: async id => {
        try {
            const { message } = await Request.delete(`${CMS}/${id}`, true);
            return message;
        } catch (e) {
            throw e;
        }
    }
};

export default obj;
