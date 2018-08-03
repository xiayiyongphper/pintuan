--
-- Created by IntelliJ IDEA.
-- User: ZQY
-- Date: 2017/6/21
-- Time: 15:44
-- 获取用户秒杀商品
--
-- KEYS : actId customerId areaId

--字符串分割函数
--传入字符串和分隔符，返回分割后的table
local function split(str, d)
    local lst = { }
    local n = string.len(str)
    local start = 1
    while start <= n do
        local i = string.find(str, d, start)
        if i == nil then
            table.insert(lst, string.sub(str, start, n))
            break
        end
        table.insert(lst, string.sub(str, start, i-1))
        if i == n then
            table.insert(lst, "")
            break
        end
        start = i + 1
    end
    return lst
end

local function getUserSecKillItems(KEYS)
    local actId = tonumber(KEYS[1]);
    local customerId = tonumber(KEYS[2]);
    local areaId = tonumber(KEYS[3]);

    local retData = { };
    local customerKey = "sk_c_" .. actId .. "_" .. customerId;
    local customerProKeys = redis.call("SMEMBERS", customerKey);
    if (customerProKeys ~= nil) then
        local tmpTable = {};
        local ttl = 0;
        local num = 0;
        for _, customerProKey in ipairs(customerProKeys) do
            tmpTable = split(customerProKey, "_");
            if (next(tmpTable) ~= nil) then
                ttl = redis.call("TTL", customerProKey);
                num = tonumber(redis.call("GET", customerProKey));
                if (num ~= nil and ttl > 0 and num > 0) then
                    retData[tmpTable[4]] = {t=ttl, n=num};
                end
            end
        end
    end
    return cjson.encode(retData);
end
return getUserSecKillItems(KEYS);
