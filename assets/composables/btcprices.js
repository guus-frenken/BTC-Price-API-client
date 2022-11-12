import {useApi} from './api';

const {get} = useApi();

const useBtcPrices = () => {
    const getCurrentBtcPrice = async (currency) => {
        return await get(`btcprice/${currency}`);
    };

    const getBtcPriceHistory = async (currency) => {
        return await get(`btcprice/${currency}/history`);
    };

    return {
        getCurrentBtcPrice,
        getBtcPriceHistory,
    };
};

export default useBtcPrices;
