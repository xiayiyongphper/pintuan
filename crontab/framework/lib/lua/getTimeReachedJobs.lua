--
-- Created by IntelliJ IDEA.
-- User: ZQY
-- Date: 2017/8/29
-- Time: 16:01
-- 获取到时间了的计划任务ID
-- KEYS : curTimestamp, {taskId1, taskId2, ...}
-- RETURN : 到时间了的计划任务IDs
--

local function getTimeReachedJobs(KEYS)
    local curTimestamp = tonumber(KEYS[1]);
    local taskIds = cjson.decode(KEYS[2]);
    local retData = {};

    if (taskIds == nil) then
        return retData;
    end

    local prefix = "crontab_task_";
    for _, taskId in ipairs(taskIds) do
        local timestamp = tonumber(redis.call("LPOP", prefix .. taskId));
        if (timestamp ~= nil and timestamp ~= false) then
            if (timestamp < curTimestamp) then
                table.insert(retData, taskId .. '#' .. timestamp);
            else
                redis.call("LPUSH", prefix .. taskId, timestamp);
            end
        end
    end

    return cjson.encode(retData);
end

return getTimeReachedJobs(KEYS);

