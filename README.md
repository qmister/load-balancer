### 简介

在分布式系统中，为了实现负载均衡，必然会涉及到负载调度算法，如 Nginx 和 RPC 服务发现等场景。常见的负载均衡算法有 轮询、源地址 Hash、最少连接数，而 轮询 是最简单且应用最广的算法。


### 简单轮询

简单轮询是轮询算法中最简单的一种，但由于它不支持配置负载，所以应用较少。

#### 算法描述

假设有 N 台实例 S = {S1, S2, …, Sn}，指示变量 currentPos 表示当前选择的实例 ID，初始化为 -1。

#### 算法描述

1、调度到下一个实例；
2、若所有实例已被 调度 过一次，则从头开始调度；
3、每次调度重复步骤 1、2；


#### 优缺点分析

在实际应用中，同一个服务会部署到不同的硬件环境，会出现性能不同的情况。若直接使用简单轮询调度算法，给每个服务实例相同的负载，那么，必然会出现资源浪费的情况。因此为了避免这种情况，一些人就提出了下面的 加权轮询 算法。

### 加权轮询

加权轮询算法引入了“权”值，改进了简单轮询算法，可以根据硬件性能配置实例负载的权重，从而达到资源的合理利用。

#### 算法描述

假设有 N 台实例 S = {S1, S2, …, Sn}，权重 W = {W1, W2, …, Wn}，指示变量 currentPos 表示当前选择的实例 ID，初始化为 -1；变量 currentWeight 表示当前权重，初始值为 max(S)；max(S) 表示 N 台实例的最大权重值，gcd(S) 表示 N 台实例权重的最大公约数。

#### 算法可以描述为：

1、从上一次调度实例起，遍历后面的每个实例；
2、若所有实例已被遍历过一次，则减小 currentWeight 为 currentWeight - gcd(S)，并从头开始遍历；若 currentWeight 小于等于 0，则重置为 max(S)；
3、直到 遍历的实例的权重大于等于 currentWeight 时结束，此时实例为需调度的实例；
4、每次调度重复步骤 1、2、3；


#### 优缺点分析

加权轮询 算法虽然通过配置实例权重，解决了 简单轮询 的资源利用问题，但是它还是存在一个比较明显的 缺陷。例如：

服务实例 S = {a, b, c}，权重 W = {5, 1, 1}，使用加权轮询调度生成的实例序列为 {a, a, a, a, a, b, c}，那么就会存在连续 5 个请求都被调度到实例 a。而实际中，这种不均匀的负载是不被允许的，因为连续请求会突然加重实例 a 的负载，可能会导致严重的事故。

为了解决加权轮询调度不均匀的缺陷，一些人提出了 平滑加权轮询 调度算法，它会生成的更均匀的调度序列 {a, a, b, a, c, a, a}。对于神秘的平滑加权轮询算法，我将在后续文章中详细介绍它的原理和实现。

