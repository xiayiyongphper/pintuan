--
-- Created by IntelliJ IDEA.
-- User: ZQY
-- Date: 2017/6/22
-- Time: 17:40
-- 获取秒杀商品库存
--
-- KEYS : actId, [proId, proId2, ...]

local function getSecKillProductStock(actId, proId)
    local totalKey = "sk_total_" .. actId .. "_" .. proId;
    local total = tonumber(redis.call("GET", totalKey));
    if (total == nil) then
        return 0;
    end

    local productKey = "sk_p_" .. actId .. "_" .. proId;
    local customerProKeys = redis.call("SMEMBERS", productKey);
    local sum = 0;
    if (customerProKeys ~= nil) then
        for _, customerProKey in ipairs(customerProKeys) do
            local proNum = tonumber(redis.call("GET", customerProKey));
            if (proNum ~= nil) then
                sum = sum + proNum;
            end
        end
    end

    if (total <= sum) then
        return 0;
    else
        return total - sum;
    end
end

local function getSecKillProductsStock(ARGS)
    local retData = {};
    local actId = tonumber(ARGS[1]);

    for i = 2, #ARGS do
        retData[ARGS[i]] = getSecKillProductStock(actId, ARGS[i]);
    end

    return cjson.encode(retData);
end
return getSecKillProductsStock(KEYS);