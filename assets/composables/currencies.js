import {useApi} from './api';

const {get} = useApi();

const useCurrencies = () => {
    const getCurrencies = async () => {
        return await get('currency');
    };

    return {
        getCurrencies,
    };
};

export default useCurrencies;
