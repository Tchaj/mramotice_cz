import Request from "./RequestService";
import obj from "../Config/App";

const STORE = "storage/avatar";
const { formHeaders } = Request;

const storage = {
    set: (key, data) => {
        if (typeof data != "string") {
            data = JSON.stringify(data);
        }
        localStorage.setItem(key, data);
    },
    get: key => {
        const data = localStorage.getItem(key);
        return data;
    },
    delete: key => {
        return localStorage.removeItem(key);
    },
    storeAvatar: async data => {
        try {
            let mediaData = new FormData();
            mediaData.append("file", data);

            return await Request.postH(STORE, mediaData, true, formHeaders);
        } catch (e) {
            throw e;
        }
    },
    storeAll: async fileData => {
        try {
            let responses = [];

            if (typeof fileData != "object") return [];

            const mapFileData = await Promise.all(
                fileData.map(async i => {
                    const s = await storage.storeAvatar(i);
                    responses = [...responses, s];
                })
            );
            return responses;
        } catch (e) {
            throw e;
        }
    },
    updateById: async obj => {
        try {
            if (
                obj.id == "undefined" ||
                !obj.id ||
                !Object.keys(obj).includes("id")
            )
                return false;
            const { message } = await Request.patch(
                `storage/${obj.id}`,
                obj,
                true
            );
            return message;
        } catch (e) {
            throw e;
        }
    },
    updateAll: async data => {
        try {
            const eachUpdate = await Promise.all(
                data.map(async obj => {
                    await storage.updateById(obj);
                })
            );

            return true;
        } catch (e) {
            throw e;
        }
    }
};

export default storage;
