--检查商品库存可用性，商品库存不足时返回对应的index位置
local function checkAvailability(_keys, _values)
    local flag = 0
    for k, v in pairs(_keys) do
        if redis.call("EXISTS", v) == 1 then
            local qty = tonumber(redis.call("GET", v))
            local num = tonumber(_values[k])
            if qty < num then
                flag = -k
            end
        else
            flag = -k
        end
        if flag < 0 then
            break
        end
    end
    return flag
end

--直接扣减库存
local function subtractInventory(_keys, _values)
    for k, v in pairs(_keys) do
        local num = tonumber(_values[k])
        redis.call("DECRBY", v, num)
    end
    return 1
end

--库存可用性
local availability = checkAvailability(KEYS, ARGV)
if availability == 0 then
    --扣减库存
    subtractInventory(KEYS, ARGV)
    return 0
else
    return availability
end