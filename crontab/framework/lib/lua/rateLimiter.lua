local function checkLimiter(_keys, _values)
    local flag = 1
    if table.getn(_keys) == 3 then
        local id = _values[1]
        local window = tonumber(_values[2])
        local size = tonumber(_values[3])
        if redis.call("EXISTS", id) == 1 then
            if redis.call("INCR", id) > size then
                redis.call("DECR", id)
                flag = -2
            end
        else
            if redis.call("INCR", id) <= size then
                redis.call("EXPIRE", id, window)
            else
                redis.call("DEL", id)
                flag = -3
            end
        end
    else
        flag = -1
    end
    return flag
end

local availability = checkLimiter(KEYS, ARGV)
return availability