--
-- Created by IntelliJ IDEA.
-- User: ZQY
-- Date: 2017/6/21
-- Time: 12:55
-- 删除购物车秒杀商品
--
local function removeItem(KEYS)
    local actId = tonumber(KEYS[1]);
    local proId = tonumber(KEYS[2]);
    local customerId = tonumber(KEYS[3]);
    local areaId = tonumber(KEYS[4]);

    local customerProKey = "sk_pc_" .. actId .. "_" .. proId .. "_" .. customerId .. "_" .. areaId ;
    local proKey = "sk_p_" .. actId .. "_" .. proId;
    local customerKey = "sk_c_" .. actId .. "_" .. customerId;

    local removeKeys = redis.call("DEL", customerProKey);
    if (removeKeys <= 0) then
        return 0;
    end

    redis.call("SREM", proKey, customerProKey);
    redis.call("SREM", customerKey, customerProKey);
    return 1;
end
return removeItem(KEYS);