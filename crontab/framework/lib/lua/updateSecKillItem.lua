--
-- Created by IntelliJ IDEA.
-- User: ZQY
-- Date: 2017/6/21
-- Time: 10:23
-- 更新购物车秒杀商品数量
--
-- KEYS : actId, proId, num, customerId, areaId
local function updateItem(KEYS)
    local actId = tonumber(KEYS[1]);
    local proId = tonumber(KEYS[2]);
    local num = tonumber(KEYS[3]);
    local customerId = tonumber(KEYS[4]);
    local areaId = tonumber(KEYS[5]);

    local expiredTime = tonumber(redis.call("GET", "sk_cart_expired_time"));
    if (expiredTime == nil or expiredTime <= 0) then
        expiredTime = 1200;
    end

    local totalKey = "sk_total_" .. actId .. "_" .. proId;
    local customerProKey = "sk_pc_" .. actId .. "_" .. proId .. "_" .. customerId .. "_" .. areaId ;
    local proKey = "sk_p_" .. actId .. "_" .. proId;
    local customerKey = "sk_c_" .. actId .. "_" .. customerId;

    local total = tonumber(redis.call("GET", totalKey));
    if (total == nil) then
        return 0;
    end

    local cartKeys = redis.call("SMEMBERS", proKey);
    if (cartKeys ~= nil) then
        local sum = 0;
        for _, cartKey in ipairs(cartKeys) do
            if (cartKey ~= customerProKey) then
                local proNum = tonumber(redis.call("GET", cartKey));
                if (proNum ~= nil) then
                    sum = sum + proNum;
                end
            end
        end
        if (total < (sum + num)) then
            return 0;
        end
    end

    if (total >= num) then
        local preExpiredTime = redis.call("TTL", customerProKey);
        if (preExpiredTime > 0) then
            expiredTime = preExpiredTime;
        end
        if (expiredTime > 0) then
            redis.call("SADD", proKey, customerProKey);
            redis.call("SADD", customerKey, customerProKey);
            redis.call("SET", customerProKey, num, "EX", expiredTime);
        end
        return 1;
    else
        return 0;
    end
end
return updateItem(KEYS);