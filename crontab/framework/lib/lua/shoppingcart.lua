--
-- Created by IntelliJ IDEA.
-- User: Jason Y. Wang
-- Date: 2017/8/29
-- Time: 10:23
-- 更新购物车商品
--
-- KEYS : customerId, wholesalerId, proId, num, selected
local function updateCartItem(KEYS)
    local customerId = tonumber(KEYS[1]);
    local wholesalerId = tonumber(KEYS[2]);
    local proId = tonumber(KEYS[3]);
    local num = tonumber(KEYS[4]);
    local selected = tonumber(KEYS[5]);
    local cur_time = tonumber(KEYS[6]);

    local cartKey = "shopping_cart_" .. customerId;
    local cartWholesalersKey = "shopping_cart_wholesaler_" .. customerId .. "_" .. wholesalerId;
    --将商家ID加入
    redis.call("ZADD", cartKey, cur_time, wholesalerId);
    --判断选中状态，未选中用复数表示
    if (selected == 0) then
        num = 0 - num;
    end
    --将商家ID加入
    redis.call("HSET", cartWholesalersKey, proId, num);
    return 1;
end

-- return updateCartItem(KEYS);


local function cartItemFromRedis(KEYS)
    local customerId = tonumber(KEYS[1]);

    local shoppingCartKey = "shopping_cart_" .. customerId;
    local wholesalerIds = redis.call("ZREVRANGEBYSCORE", shoppingCartKey, "+inf", "-inf");

    local cartItems = {};

    for key, wholesalerId in pairs(wholesalerIds) do
        local shoppingCartWholesalerKey = "shopping_cart_wholesaler_" .. customerId .. "_" .. wholesalerId;
        local products = redis.call("HGETALL", shoppingCartWholesalerKey);
        if (next(products) ~= nil) then
            local productsTable = {};
            local nextkey;
            for key, value in pairs(products) do
                if key % 2 == 1 then
                    nextkey = value
                else
                    productsTable[nextkey] = value
                end
            end
            cartItems[wholesalerId] = productsTable;
        end
    end

    return cjson.encode(cartItems);
end

-- return cartItemFromRedis(KEYS);

local function updateCartItem(KEYS)
    local customerId = tonumber(KEYS[1]);
    local productAvailable = cjson.decode(KEYS[2]);
    local cur_time = tonumber(KEYS[3]);
    local cartKey = "shopping_cart_" .. customerId;

    for wholesalerId, products in pairs(productAvailable) do
        local shoppingCartWholesalerKey = "shopping_cart_wholesaler_" .. customerId .. "_" .. wholesalerId;
        redis.call("ZADD", cartKey, cur_time, wholesalerId);
        if (next(products) ~= nil) then
            for productId, num in pairs(products) do
                redis.call("HSET", shoppingCartWholesalerKey, productId, num);
            end
        end
    end

    return 1;
end

-- return updateCartItem(KEYS);

local function test(KEYS)
    local customerId = tonumber(KEYS[1]);
    local test = {};
    test["1"] = "test";
    test["2"] = "test2";
    local result = {};
    result["1"] = test;
    return result;
end

return test(KEYS);

